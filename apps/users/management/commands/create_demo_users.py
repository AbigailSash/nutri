from django.core.management.base import BaseCommand
from django.db import transaction
from apps.users.models import User, Person, Patient, Consultation, AnthropometricMeasurement
from datetime import date, datetime


class Command(BaseCommand):
    help = 'Crear usuarios de demostración para el sistema de nutrición'

    def handle(self, *args, **options):
        with transaction.atomic():
            self.stdout.write('Creando usuarios de demostración...')
            
            # Crear nutricionista
            nutricionista, created = User.objects.get_or_create(
                dni='12345678',
                defaults={
                    'username': 'nutricionista',
                    'first_name': 'Leila',
                    'last_name': 'Olmedo',
                    'email': 'leila.olmedo@nutrisalud.com',
                    'role': 'nutricionista',
                    'is_staff': True,
                    'is_superuser': True
                }
            )
            if created:
                nutricionista.set_password('nutri123')
                nutricionista.save()
                self.stdout.write(f'Nutricionista creada: {nutricionista.dni}')
            
            # Crear persona del nutricionista
            person_nutri, created = Person.objects.get_or_create(
                user=nutricionista,
                defaults={
                    'birth_date': date(1985, 3, 15),
                    'phone': '+54911234567',
                    'address': 'Av. Corrientes 1234, Buenos Aires'
                }
            )
            
            # Crear pacientes de ejemplo
            patients_data = [
                {
                    'dni': '20123456',
                    'first_name': 'Ana',
                    'last_name': 'García',
                    'email': 'ana.garcia@email.com',
                    'birth_date': date(1992, 8, 20),
                    'phone': '+54911111111',
                    'has_diabetes': False,
                    'has_hypertension': True,
                },
                {
                    'dni': '20234567',
                    'first_name': 'Carlos',
                    'last_name': 'López',
                    'email': 'carlos.lopez@email.com',
                    'birth_date': date(1988, 12, 5),
                    'phone': '+54922222222',
                    'has_diabetes': True,
                    'has_hypertension': False,
                },
                {
                    'dni': '20345678',
                    'first_name': 'María',
                    'last_name': 'Rodríguez',
                    'email': 'maria.rodriguez@email.com',
                    'birth_date': date(1995, 6, 10),
                    'phone': '+54933333333',
                    'has_diabetes': False,
                    'has_hypertension': False,
                }
            ]
            
            for patient_data in patients_data:
                # Crear usuario paciente
                user, created = User.objects.get_or_create(
                    dni=patient_data['dni'],
                    defaults={
                        'username': f"paciente_{patient_data['dni']}",
                        'first_name': patient_data['first_name'],
                        'last_name': patient_data['last_name'],
                        'email': patient_data['email'],
                        'role': 'paciente'
                    }
                )
                if created:
                    user.set_password('paciente123')
                    user.save()
                    
                    # Crear persona
                    person, _ = Person.objects.get_or_create(
                        user=user,
                        defaults={
                            'birth_date': patient_data['birth_date'],
                            'phone': patient_data['phone'],
                            'address': f'Dirección de {patient_data["first_name"]} {patient_data["last_name"]}'
                        }
                    )
                    
                    # Crear paciente
                    patient, _ = Patient.objects.get_or_create(
                        person=person,
                        defaults={
                            'has_diabetes': patient_data['has_diabetes'],
                            'has_hypertension': patient_data['has_hypertension'],
                            'medical_history': f'Historial médico de {patient_data["first_name"]}',
                            'allergies': 'Ninguna alergia conocida'
                        }
                    )
                    
                    # Crear consulta inicial
                    consultation = Consultation.objects.create(
                        patient=patient,
                        nutritionist=nutricionista,
                        consultation_type='inicial',
                        notes=f'Consulta inicial para {patient_data["first_name"]}'
                    )
                    
                    # Crear medidas antropométricas
                    AnthropometricMeasurement.objects.create(
                        consultation=consultation,
                        weight=70 + (hash(patient_data['dni']) % 30),  # Peso simulado
                        height=1.65 + ((hash(patient_data['dni']) % 20) / 100),  # Altura simulada
                        waist_circumference=80 + (hash(patient_data['dni']) % 15),
                        hip_circumference=95 + (hash(patient_data['dni']) % 10)
                    )
                    
                    self.stdout.write(f'Paciente creado: {user.dni} - {user.get_full_name()}')
            
            self.stdout.write(self.style.SUCCESS('\n=== USUARIOS CREADOS ==='))
            self.stdout.write('Nutricionista:')
            self.stdout.write(f'  DNI: 12345678, Contraseña: nutri123')
            self.stdout.write('Pacientes:')
            for patient_data in patients_data:
                self.stdout.write(f'  DNI: {patient_data["dni"]}, Contraseña: paciente123')
            self.stdout.write('\nPuedes usar estos datos para hacer login en el sistema.')