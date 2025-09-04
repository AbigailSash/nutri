from rest_framework import status, generics, permissions
from rest_framework.response import Response
from rest_framework.views import APIView
from rest_framework_simplejwt.tokens import RefreshToken
from rest_framework_simplejwt.views import TokenObtainPairView
from django.contrib.auth import login
from django.core.mail import send_mail
from django.conf import settings
import logging

from .models import User, Patient, PatientInvitation
from .serializers import (
    UserSerializer, 
    LoginSerializer, 
    RegisterSerializer,
    PatientSerializer,
    PatientCreateSerializer,
    PatientUpdateSerializer,
    ChangePasswordSerializer,
    PasswordResetSerializer,
    PatientInvitationCreateSerializer,
    PatientInvitationSerializer,
    AcceptInvitationSerializer
)

logger = logging.getLogger(__name__)


class CustomTokenObtainPairView(TokenObtainPairView):
    serializer_class = LoginSerializer

    def post(self, request, *args, **kwargs):
        serializer = LoginSerializer(data=request.data, context={'request': request})
        if serializer.is_valid():
            user = serializer.validated_data['user']
            
            # Generar tokens JWT
            refresh = RefreshToken.for_user(user)
            
            # Datos del usuario
            user_data = UserSerializer(user).data
            
            return Response({
                'refresh': str(refresh),
                'access': str(refresh.access_token),
                'user': user_data,
                'message': 'Login exitoso'
            }, status=status.HTTP_200_OK)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


class RegisterView(generics.CreateAPIView):
    queryset = User.objects.all()
    serializer_class = RegisterSerializer
    permission_classes = [permissions.AllowAny]  # Solo admin puede registrar usuarios

    def create(self, request, *args, **kwargs):
        # Verificar que solo admin puede registrar usuarios
        if not request.user.is_authenticated or not request.user.is_staff:
            return Response(
                {'error': 'Solo administradores pueden registrar usuarios'}, 
                status=status.HTTP_403_FORBIDDEN
            )
        
        serializer = self.get_serializer(data=request.data)
        if serializer.is_valid():
            user = serializer.save()
            user_data = UserSerializer(user).data
            return Response({
                'user': user_data,
                'message': 'Usuario registrado exitosamente'
            }, status=status.HTTP_201_CREATED)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


class LogoutView(APIView):
    permission_classes = [permissions.IsAuthenticated]

    def post(self, request):
        try:
            refresh_token = request.data["refresh"]
            token = RefreshToken(refresh_token)
            token.blacklist()
            return Response({'message': 'Logout exitoso'}, status=status.HTTP_200_OK)
        except Exception as e:
            return Response({'error': 'Token inválido'}, status=status.HTTP_400_BAD_REQUEST)


class UserProfileView(generics.RetrieveUpdateAPIView):
    serializer_class = UserSerializer
    permission_classes = [permissions.IsAuthenticated]

    def get_object(self):
        return self.request.user

    def get(self, request, *args, **kwargs):
        user = self.get_object()
        user_data = UserSerializer(user).data
        
        # Agregar datos adicionales según el rol
        if user.role == 'paciente' and hasattr(user, 'person') and hasattr(user.person, 'patient'):
            user_data['patient_data'] = PatientSerializer(user.person.patient).data
            
        return Response({
            'user': user_data,
            'role': user.role
        })


class ChangePasswordView(APIView):
    permission_classes = [permissions.IsAuthenticated]

    def post(self, request):
        serializer = ChangePasswordSerializer(data=request.data, context={'request': request})
        if serializer.is_valid():
            user = request.user
            user.set_password(serializer.validated_data['new_password'])
            user.save()
            
            return Response({'message': 'Contraseña cambiada exitosamente'}, status=status.HTTP_200_OK)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


class PasswordResetView(APIView):
    permission_classes = [permissions.AllowAny]

    def post(self, request):
        serializer = PasswordResetSerializer(data=request.data)
        if serializer.is_valid():
            email = serializer.validated_data['email']
            user = User.objects.get(email=email)
            
            # Generar token de reset (simplificado para MVP)
            reset_token = RefreshToken.for_user(user)
            
            # En MVP, solo loggear a consola
            logger.info(f"Password reset for {email}. Token: {reset_token}")
            print(f"=== PASSWORD RESET ===")
            print(f"Email: {email}")
            print(f"Reset Token: {reset_token}")
            print(f"========================")
            
            return Response({
                'message': 'Se ha enviado un email con instrucciones para restablecer tu contraseña'
            }, status=status.HTTP_200_OK)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


# Vistas para el CRUD de pacientes (solo para nutricionistas)
class PatientListView(generics.ListCreateAPIView):
    serializer_class = PatientSerializer
    permission_classes = [permissions.IsAuthenticated]

    def get_queryset(self):
        # Solo nutricionistas pueden ver pacientes
        if self.request.user.role == 'nutricionista':
            return Patient.objects.all()
        return Patient.objects.none()

    def get_serializer_class(self):
        if self.request.method == 'POST':
            return PatientCreateSerializer
        return PatientSerializer

    def create(self, request, *args, **kwargs):
        if request.user.role != 'nutricionista':
            return Response(
                {'error': 'Solo nutricionistas pueden crear pacientes'}, 
                status=status.HTTP_403_FORBIDDEN
            )
        
        serializer = self.get_serializer(data=request.data)
        
        if serializer.is_valid():
            patient = serializer.save()
            return Response({
                'message': 'Paciente creado exitosamente',
                'patient': PatientSerializer(patient).data
            }, status=status.HTTP_201_CREATED)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


# Nueva vista para invitar pacientes
class PatientInviteView(generics.CreateAPIView):
    serializer_class = PatientInvitationCreateSerializer
    permission_classes = [permissions.IsAuthenticated]
    
    def create(self, request, *args, **kwargs):
        if request.user.role != 'nutricionista':
            return Response(
                {'error': 'Solo nutricionistas pueden invitar pacientes'}, 
                status=status.HTTP_403_FORBIDDEN
            )
        
        serializer = self.get_serializer(data=request.data)
        if serializer.is_valid():
            invitation = serializer.save()
            
            # En MVP, mostrar en consola el enlace de invitación
            invitation_link = f"http://localhost:5175/auth/complete-registration?token={invitation.token}"
            print(f"\n=== INVITACIÓN DE PACIENTE CREADA ===")
            print(f"Paciente: {invitation.first_name} {invitation.last_name}")
            print(f"Email: {invitation.email}")
            print(f"DNI: {invitation.dni}")
            print(f"Link de invitación: {invitation_link}")
            print(f"Expira: {invitation.expires_at.strftime('%d/%m/%Y %H:%M')}")
            print(f"=====================================\n")
            
            return Response({
                'message': 'Invitación enviada exitosamente',
                'invitation': PatientInvitationSerializer(invitation).data,
                'invitation_link': invitation_link  # Solo para MVP/desarrollo
            }, status=status.HTTP_201_CREATED)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


# Vista para listar invitaciones pendientes
class PatientInvitationListView(generics.ListAPIView):
    serializer_class = PatientInvitationSerializer
    permission_classes = [permissions.IsAuthenticated]
    
    def get_queryset(self):
        if self.request.user.role == 'nutricionista':
            return PatientInvitation.objects.filter(invited_by=self.request.user)
        return PatientInvitation.objects.none()


# Vista para que el paciente complete su registro
class CompleteRegistrationView(generics.CreateAPIView):
    serializer_class = AcceptInvitationSerializer
    permission_classes = [permissions.AllowAny]
    
    def create(self, request, *args, **kwargs):
        serializer = self.get_serializer(data=request.data)
        if serializer.is_valid():
            user = serializer.save()
            
            # Generar tokens JWT para el nuevo usuario
            refresh = RefreshToken.for_user(user)
            user_data = UserSerializer(user).data
            
            return Response({
                'message': 'Registro completado exitosamente',
                'refresh': str(refresh),
                'access': str(refresh.access_token),
                'user': user_data
            }, status=status.HTTP_201_CREATED)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


# Vista para obtener detalles de una invitación
class InvitationDetailView(APIView):
    permission_classes = [permissions.AllowAny]
    
    def get(self, request, token):
        try:
            invitation = PatientInvitation.objects.get(token=token)
        except PatientInvitation.DoesNotExist:
            return Response(
                {'error': 'Token de invitación inválido'}, 
                status=status.HTTP_404_NOT_FOUND
            )
        
        if invitation.status != 'pending':
            return Response(
                {'error': 'Esta invitación ya fue procesada'}, 
                status=status.HTTP_400_BAD_REQUEST
            )
        
        if invitation.is_expired:
            invitation.mark_as_expired()
            return Response(
                {'error': 'Esta invitación ha expirado'}, 
                status=status.HTTP_400_BAD_REQUEST
            )
        
        return Response({
            'invitation': {
                'first_name': invitation.first_name,
                'last_name': invitation.last_name,
                'email': invitation.email,
                'dni': invitation.dni,
                'expires_at': invitation.expires_at,
                'invited_by': invitation.invited_by.get_full_name()
            }
        })


class PatientDetailView(generics.RetrieveUpdateDestroyAPIView):
    permission_classes = [permissions.IsAuthenticated]

    def get_queryset(self):
        if self.request.user.role == 'nutricionista':
            return Patient.objects.all()
        elif self.request.user.role == 'paciente':
            # Paciente solo puede ver su propio perfil
            return Patient.objects.filter(person__user=self.request.user)
        return Patient.objects.none()

    def get_serializer_class(self):
        if self.request.method in ['PUT', 'PATCH']:
            return PatientUpdateSerializer
        return PatientSerializer

    def update(self, request, *args, **kwargs):
        if request.user.role not in ['nutricionista', 'paciente']:
            return Response(
                {'error': 'No tienes permisos para actualizar'}, 
                status=status.HTTP_403_FORBIDDEN
            )
        
        partial = kwargs.pop('partial', False)
        instance = self.get_object()
        serializer = self.get_serializer(instance, data=request.data, partial=partial)
        
        if serializer.is_valid():
            patient = serializer.save()
            return Response({
                'message': 'Paciente actualizado exitosamente',
                'patient': PatientSerializer(patient).data
            }, status=status.HTTP_200_OK)
        
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    def destroy(self, request, *args, **kwargs):
        if request.user.role != 'nutricionista':
            return Response(
                {'error': 'Solo nutricionistas pueden eliminar pacientes'}, 
                status=status.HTTP_403_FORBIDDEN
            )
        
        instance = self.get_object()
        patient_name = f"{instance.person.user.first_name} {instance.person.user.last_name}"
        
        # Eliminar usuario (esto eliminará en cascada person y patient)
        instance.person.user.delete()
        
        return Response({
            'message': f'Paciente {patient_name} eliminado exitosamente'
        }, status=status.HTTP_200_OK)
