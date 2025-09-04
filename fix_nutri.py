#!/usr/bin/env python
import os
import sys
import django

# Añadir el directorio del proyecto al path
sys.path.append('C:\\Users\\MatiB\\OneDrive\\Escritorio\\PROYECTO DE ABYCHUELAS\\sistema-nutricion')

# Configurar Django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'core.settings')
django.setup()

from apps.users.models import User, Person

try:
    # Buscar usuario nutri
    nutri = User.objects.get(dni='12345678')
    print(f"Usuario encontrado: {nutri.username}, Activo: {nutri.is_active}")
    
    # Activar usuario
    nutri.is_active = True
    nutri.save()
    
    print(f"Usuario activado exitosamente: {nutri.username}, Activo: {nutri.is_active}")
    print("Verificando contraseña...")
    print(f"Contraseña nutri123 es válida: {nutri.check_password('nutri123')}")
    
except User.DoesNotExist:
    print("Usuario nutri no encontrado")
except Exception as e:
    print(f"Error: {e}")