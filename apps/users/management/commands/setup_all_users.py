from django.core.management.base import BaseCommand
from apps.users.models import User, Person

class Command(BaseCommand):
    help = 'Setup complete user system with admin, nutri and test users'

    def handle(self, *args, **options):
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
        Person.objects.create(user=admin)
        self.stdout.write("ADMIN creado: DNI 00000000, user: admin, pass: admin123")
        
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
            is_staff=True  # Staff para acceder al admin
        )
        Person.objects.create(user=nutri)
        self.stdout.write("NUTRICIONISTA creado: DNI 12345678, user: nutricionista, pass: nutri123")
        
        # 3. CREAR NUTRICIONISTA ALTERNATIVO
        nutri2 = User.objects.create_user(
            dni='11111111',
            username='nutri_activo',
            first_name='Dra. María',
            last_name='Nutritiva',
            email='maria@nutrisalud.com',
            password='nutri123',
            role='nutricionista',
            is_active=True,
            is_staff=True
        )
        Person.objects.create(user=nutri2)
        self.stdout.write("NUTRICIONISTA 2 creado: DNI 11111111, user: nutri_activo, pass: nutri123")
        
        # 4. CREAR PACIENTES DE PRUEBA
        pacientes = [
            {
                'dni': '20234567',
                'first_name': 'Carlos',
                'last_name': 'López',
                'email': 'carlos.lopez@email.com',
                'password': 'paciente123'
            },
            {
                'dni': '20345678', 
                'first_name': 'María',
                'last_name': 'Rodríguez',
                'email': 'maria.rodriguez@email.com',
                'password': 'paciente123'
            },
            {
                'dni': '30456789',
                'first_name': 'Ana',
                'last_name': 'García',
                'email': 'ana.garcia@email.com', 
                'password': 'paciente123'
            }
        ]
        
        for p in pacientes:
            paciente = User.objects.create_user(
                dni=p['dni'],
                username=f"paciente_{p['dni']}",
                first_name=p['first_name'],
                last_name=p['last_name'],
                email=p['email'],
                password=p['password'],
                role='paciente',
                is_active=True
            )
            Person.objects.create(user=paciente)
            self.stdout.write(f"PACIENTE creado: DNI {p['dni']}, {p['first_name']} {p['last_name']}, pass: paciente123")
        
        self.stdout.write("\n=== USUARIOS FINALES ===")
        for user in User.objects.all().order_by('role', 'dni'):
            self.stdout.write(
                f"DNI: {user.dni:<10} | "
                f"Usuario: {user.username:<15} | "
                f"Rol: {user.role:<12} | "
                f"Activo: {user.is_active} | "
                f"Staff: {user.is_staff} | "
                f"Super: {user.is_superuser}"
            )
        
        self.stdout.write(f"\n{self.style.SUCCESS('SISTEMA DE USUARIOS CONFIGURADO CORRECTAMENTE')}")
        self.stdout.write("\n=== CREDENCIALES DE ACCESO ===")
        self.stdout.write("ADMIN: DNI 00000000, password: admin123")
        self.stdout.write("NUTRI 1: DNI 12345678, password: nutri123") 
        self.stdout.write("NUTRI 2: DNI 11111111, password: nutri123")
        self.stdout.write("PACIENTES: DNI 20234567/20345678/30456789, password: paciente123")