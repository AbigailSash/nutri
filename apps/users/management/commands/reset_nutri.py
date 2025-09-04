from django.core.management.base import BaseCommand
from apps.users.models import User, Person

class Command(BaseCommand):
    help = 'Reset and create nutricionista user'

    def handle(self, *args, **options):
        # Mostrar todos los usuarios existentes
        self.stdout.write("=== USUARIOS EXISTENTES ===")
        for user in User.objects.all():
            self.stdout.write(f"DNI: {user.dni}, Username: {user.username}, Active: {user.is_active}, Role: {user.role}")
        
        # Eliminar usuario existente si existe
        try:
            old_user = User.objects.get(dni='12345678')
            self.stdout.write(f"Eliminando usuario existente: {old_user.username}")
            old_user.delete()
        except User.DoesNotExist:
            pass
            
        # Eliminar usuario activo si existe
        try:
            old_user = User.objects.get(dni='11111111')
            self.stdout.write(f"Eliminando usuario activo existente: {old_user.username}")
            old_user.delete()
        except User.DoesNotExist:
            pass
        
        # Crear nuevo usuario nutricionista
        nutri = User.objects.create_user(
            dni='12345678',
            username='nutricionista',
            first_name='Dr.',
            last_name='Nutricionista',
            email='nutri@example.com',
            password='nutri123',
            role='nutricionista',
            is_active=True  # IMPORTANTE: Activo desde el inicio
        )
        
        # Crear Person asociado
        Person.objects.create(user=nutri)
        
        self.stdout.write(
            self.style.SUCCESS('Usuario nutricionista recreado:')
        )
        self.stdout.write(f'   DNI: 12345678')
        self.stdout.write(f'   Username: {nutri.username}')
        self.stdout.write(f'   Password: nutri123')
        self.stdout.write(f'   Active: {nutri.is_active}')
        self.stdout.write(f'   Role: {nutri.role}')
        
        # Verificar que funciona
        self.stdout.write("\n=== VERIFICACIÓN ===")
        self.stdout.write(f"Password check: {nutri.check_password('nutri123')}")
        
        # Mostrar usuarios finales
        self.stdout.write("\n=== USUARIOS FINALES ===")
        for user in User.objects.all():
            self.stdout.write(f"DNI: {user.dni}, Username: {user.username}, Active: {user.is_active}, Role: {user.role}")