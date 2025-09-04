from django.core.management.base import BaseCommand
from django.db import transaction
from apps.users.models import User, Person, Patient
from datetime import date

class Command(BaseCommand):
    help = 'Setup complete user system with default users for testing'

    def handle(self, *args, **options):
        with transaction.atomic():
            self.stdout.write("=== LIMPIANDO USUARIOS EXISTENTES ===")
            
            # Limpiar todos los usuarios existentes
            User.objects.all().delete()
            self.stdout.write("Todos los usuarios eliminados")
            
            self.stdout.write("\n=== CREANDO USUARIOS DEL SISTEMA ===")
            
            # 1. CREAR SUPERUSUARIO ADMINISTRADOR
            admin = User.objects.create_superuser(
                dni='00000000',
                username='admin',
                first_name='Super',
                last_name='Administrador',
                email='admin@nutrisalud.com',
                password='admin123',
                role='nutricionista',
                is_active=True,
                is_staff=True,
                is_superuser=True
            )
            Person.objects.create(
                user=admin,
                birth_date=date(1980, 1, 1),
                phone='+54911000000',
                address='Administración Central'
            )
            self.stdout.write("ADMIN creado: DNI 00000000, password: admin123")
            
            # 2. CREAR NUTRICIONISTA PRINCIPAL
            nutri = User.objects.create_user(
                dni='12345678',
                username='nutricionista',
                first_name='Dr. Juan',
                last_name='Nutricionista',
                email='nutri@nutrisalud.com',
                password='nutri123',
                role='nutricionista',
                is_active=True,
                is_staff=True
            )
            Person.objects.create(
                user=nutri,
                birth_date=date(1985, 5, 15),
                phone='+54911234567',
                address='Consultorio Nutrición'
            )
            self.stdout.write("NUTRICIONISTA creado: DNI 12345678, password: nutri123")
            
            # 3. CREAR PACIENTES DE PRUEBA
            pacientes_data = [
                {
                    'dni': '20234567',
                    'first_name': 'Carlos',
                    'last_name': 'López',
                    'email': 'carlos.lopez@email.com',
                    'birth_date': date(1990, 8, 20),
                    'phone': '+54911111111',
                    'address': 'Calle Falsa 123',
                    'has_diabetes': False,
                    'has_hypertension': True,
                },
                {
                    'dni': '20345678', 
                    'first_name': 'María',
                    'last_name': 'Rodríguez',
                    'email': 'maria.rodriguez@email.com',
                    'birth_date': date(1992, 12, 5),
                    'phone': '+54922222222',
                    'address': 'Avenida Principal 456',
                    'has_diabetes': True,
                    'has_hypertension': False,
                },
                {
                    'dni': '30456789',
                    'first_name': 'Ana',
                    'last_name': 'García',
                    'email': 'ana.garcia@email.com',
                    'birth_date': date(1995, 6, 10),
                    'phone': '+54933333333',
                    'address': 'Boulevard Central 789',
                    'has_diabetes': False,
                    'has_hypertension': False,
                }
            ]
            
            for p_data in pacientes_data:
                # Crear usuario paciente
                paciente = User.objects.create_user(
                    dni=p_data['dni'],
                    username=f"paciente_{p_data['dni']}",
                    first_name=p_data['first_name'],
                    last_name=p_data['last_name'],
                    email=p_data['email'],
                    password='paciente123',
                    role='paciente',
                    is_active=True
                )
                
                # Crear persona
                person = Person.objects.create(
                    user=paciente,
                    birth_date=p_data['birth_date'],
                    phone=p_data['phone'],
                    address=p_data['address']
                )
                
                # Crear paciente con información médica
                Patient.objects.create(
                    person=person,
                    assigned_nutritionist=nutri,
                    has_diabetes=p_data['has_diabetes'],
                    has_hypertension=p_data['has_hypertension'],
                    medical_history=f'Historial médico de {p_data["first_name"]}',
                    allergies='Sin alergias conocidas'
                )
                
                self.stdout.write(f"PACIENTE creado: DNI {p_data['dni']}, {p_data['first_name']} {p_data['last_name']}")
            
            self.stdout.write("\n=== USUARIOS CREADOS ===")
            for user in User.objects.all().order_by('role', 'dni'):
                self.stdout.write(
                    f"DNI: {user.dni:<10} | "
                    f"Rol: {user.role:<12} | "
                    f"Nombre: {user.get_full_name():<20} | "
                    f"Email: {user.email}"
                )
            
            self.stdout.write(f"\n{self.style.SUCCESS('SISTEMA DE USUARIOS CONFIGURADO CORRECTAMENTE')}")
            self.stdout.write("\n=== CREDENCIALES DE ACCESO ===")
            self.stdout.write("ADMIN: DNI 00000000, password: admin123")
            self.stdout.write("NUTRICIONISTA: DNI 12345678, password: nutri123") 
            self.stdout.write("PACIENTES: password: paciente123")
            self.stdout.write("  - Carlos López: DNI 20234567")
            self.stdout.write("  - María Rodríguez: DNI 20345678")
            self.stdout.write("  - Ana García: DNI 30456789")