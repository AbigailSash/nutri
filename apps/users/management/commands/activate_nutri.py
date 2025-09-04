from django.core.management.base import BaseCommand
from apps.users.models import User

class Command(BaseCommand):
    help = 'Activate nutricionista user'

    def handle(self, *args, **options):
        try:
            nutri = User.objects.get(dni='12345678')
            nutri.is_active = True
            nutri.save()
            self.stdout.write(
                self.style.SUCCESS(f'Usuario {nutri.username} activado exitosamente')
            )
        except User.DoesNotExist:
            self.stdout.write(
                self.style.ERROR('Usuario nutri no encontrado')
            )