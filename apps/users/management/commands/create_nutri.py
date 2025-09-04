from django.core.management.base import BaseCommand
from apps.users.models import User, Person

class Command(BaseCommand):
    help = 'Create active nutricionista user'

    def handle(self, *args, **options):
        try:
            # Verificar si ya existe
            existing_user = User.objects.filter(dni='11111111').first()
            if existing_user:
                existing_user.is_active = True
                existing_user.save()
                self.stdout.write(
                    self.style.SUCCESS(f'Usuario {existing_user.username} activado')
                )
                return
            
            # Crear nuevo usuario nutricionista activo
            nutri = User.objects.create_user(
                dni='11111111',
                username='nutri_activo',
                first_name='Doctor',
                last_name='Nutricionista',
                email='nutri@example.com',
                password='nutri123',
                role='nutricionista',
                is_active=True
            )
            
            # Crear Person asociado
            Person.objects.create(user=nutri)
            
            self.stdout.write(
                self.style.SUCCESS(f'Usuario nutri activo creado: DNI 11111111, password: nutri123')
            )
            
        except Exception as e:
            self.stdout.write(
                self.style.ERROR(f'Error: {e}')
            )