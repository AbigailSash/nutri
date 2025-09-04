from django.contrib.auth.backends import ModelBackend
from django.contrib.auth import get_user_model
from django.db.models import Q

User = get_user_model()

class DNIAuthenticationBackend(ModelBackend):
    """
    Backend de autenticación personalizado que permite login con DNI
    """
    def authenticate(self, request, username=None, password=None, dni=None, **kwargs):
        # Permitir autenticación por DNI or username
        if dni is None:
            dni = username
            
        if dni is None or password is None:
            return None
        
        try:
            # Buscar usuario por DNI
            user = User.objects.get(dni=dni)
        except User.DoesNotExist:
            return None
        
        # Verificar contraseña y que el usuario pueda autenticarse
        if user.check_password(password) and self.user_can_authenticate(user):
            return user
        
        return None
        
    def get_user(self, user_id):
        try:
            return User.objects.get(pk=user_id)
        except User.DoesNotExist:
            return None