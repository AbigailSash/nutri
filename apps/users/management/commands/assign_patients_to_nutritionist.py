from django.core.management.base import BaseCommand
from apps.users.models import User, Patient

class Command(BaseCommand):
    help = 'Asigna pacientes existentes sin nutricionista al primer nutricionista disponible'
    
    def handle(self, *args, **options):
        # Obtener el primer nutricionista disponible (excluyendo admin)
        try:
            nutritionist = User.objects.filter(
                role='nutricionista', 
                is_active=True,
                is_staff=False  # Excluir admins
            ).first()
            
            if not nutritionist:
                # Si no hay nutricionistas normales, usar cualquier nutricionista activo
                nutritionist = User.objects.filter(role='nutricionista', is_active=True).first()
                
            if not nutritionist:
                self.stdout.write(
                    self.style.ERROR('No se encontró ningún nutricionista activo')
                )
                return
            
            # Buscar pacientes sin nutricionista asignado
            patients_without_nutritionist = Patient.objects.filter(assigned_nutritionist__isnull=True)
            
            if not patients_without_nutritionist.exists():
                self.stdout.write(
                    self.style.SUCCESS('Todos los pacientes ya tienen nutricionista asignado')
                )
                return
            
            # Asignar el nutricionista a todos los pacientes sin asignar
            count = patients_without_nutritionist.update(assigned_nutritionist=nutritionist)
            
            self.stdout.write(
                self.style.SUCCESS(
                    f'Se asignaron {count} pacientes al nutricionista {nutritionist.get_full_name()} ({nutritionist.dni})'
                )
            )
            
        except Exception as e:
            self.stdout.write(
                self.style.ERROR(f'Error al asignar pacientes: {str(e)}')
            )