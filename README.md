# 🥗 Sistema de Gestión Integral para Nutricionistas

MVP completo y funcional de un sistema de gestión nutricional que conecta nutricionistas profesionales con pacientes. Desarrollado con Django REST Framework y React.

## 🎯 Características del MVP

- 🌐 **Landing page completa** con hero, características y CTAs
- 🔐 **Autenticación JWT** con refresh automático y manejo de errores
- 👩‍⚕️ **Dashboard Nutricionista** con métricas y actividad reciente
- 👤 **Dashboard Paciente** con plan diario y seguimiento de progreso
- 🛡️ **Rutas protegidas** con redirección automática por roles
- 📱 **Diseño responsive** para móvil, tablet y desktop
- ♿ **Accesibilidad WCAG 2.2 AA** completa
- 🎨 **UI/UX profesional** con Tailwind CSS

## 🏗️ Stack Tecnológico

**Frontend:**
- React 18 + JavaScript (sin TypeScript)
- Vite como bundler
- Tailwind CSS para estilos
- Axios para HTTP requests
- React Router para navegación
- Context API para estado global

**Backend:**
- Django 4.2.5
- Django REST Framework 3.14.0
- SimpleJWT para autenticación
- SQLite (desarrollo) / PostgreSQL (producción)
- CORS Headers para integración frontend

## Estructura del Proyecto

```
sistema-nutricion/
├── backend/
│   ├── nutri_core/
│   ├── users/
│   ├── manage.py
│   └── requirements.txt
├── frontend/
│   ├── src/
│   ├── public/
│   ├── package.json
│   └── vite.config.ts
└── README.md
```

## 🚀 Instalación y Configuración

### Requisitos Previos
- Python 3.8+ 
- Node.js 16+
- npm

### 🔧 Backend (Django)

1. **Navegar al directorio backend:**
```bash
cd backend
```

2. **Crear y activar entorno virtual:**
```bash
python -m venv venv

# En Windows:
venv\Scripts\activate

# En macOS/Linux:
source venv/bin/activate
```

3. **Instalar dependencias:**
```bash
pip install -r requirements.txt
```

4. **Ejecutar migraciones:**
```bash
python manage.py makemigrations
python manage.py migrate
```

5. **Crear usuarios de demostración:**
```bash
python manage.py seed_demo_users
```

6. **Ejecutar servidor:**
```bash
python manage.py runserver
```

### ⚛️ Frontend (React)

1. **Navegar al directorio frontend:**
```bash
cd frontend
```

2. **Instalar dependencias:**
```bash
npm install
```

3. **Crear archivo .env (opcional):**
```bash
echo "VITE_API_URL=http://localhost:8000/api" > .env
```

4. **Ejecutar servidor de desarrollo:**
```bash
npm run dev
```

## 🔐 Variables de Entorno

### Backend (.env) - Opcional
```
SECRET_KEY=tu_clave_secreta_aqui
DEBUG=True
ALLOWED_HOSTS=localhost,127.0.0.1
```

## 👥 Usuarios de Demostración

El comando `python manage.py seed_demo_users` crea automáticamente:

### 👩‍⚕️ Nutricionista
- **Email**: `nutricionista@test.com`
- **Contraseña**: `Test1234!`
- **Rol**: NUTRICIONISTA
- **Perfil**: Incluye matrícula, especialidad y datos profesionales

### 👤 Paciente
- **Email**: `paciente@test.com`
- **Contraseña**: `Test1234!`
- **Rol**: PACIENTE
- **Perfil**: Incluye datos médicos, objetivos y mediciones

## 🌐 URLs del Sistema

- **Landing Page**: http://localhost:5173
- **Login**: http://localhost:5173/auth/login
- **Registro**: http://localhost:5173/auth/register
- **Dashboard Nutricionista**: http://localhost:5173/nutricionista/dashboard
- **Dashboard Paciente**: http://localhost:5173/paciente/dashboard
- **API Backend**: http://localhost:8000/api/
- **Admin Django**: http://localhost:8000/admin/

## 📋 Funcionalidades Implementadas

### 🏠 Landing Page
- ✅ Hero section con llamadas a la acción
- ✅ Sección de características del servicio
- ✅ Header responsive con menú móvil
- ✅ Call-to-action final con beneficios
- ✅ Navegación fluida entre secciones

### 🔐 Sistema de Autenticación
- ✅ Registro con validación de campos
- ✅ Login con manejo de errores
- ✅ JWT con refresh automático
- ✅ Logout seguro
- ✅ Redirección automática por roles
- ✅ Persistencia de sesión

### 👩‍⚕️ Dashboard Nutricionista
- ✅ Métricas de pacientes activos
- ✅ Actividad reciente con estado de tareas
- ✅ Acciones rápidas (agregar paciente, crear plan, etc.)
- ✅ Navegación específica para nutricionistas
- ✅ Estadísticas de consultas y satisfacción

### 👤 Dashboard Paciente
- ✅ Seguimiento de peso, IMC y progreso
- ✅ Plan nutricional diario interactivo
- ✅ Lista de próximas citas médicas
- ✅ Acciones rápidas (registrar peso, agendar citas)
- ✅ Meta de hidratación y calorías

### 🛡️ Seguridad y Navegación
- ✅ Rutas protegidas por autenticación
- ✅ Guardas de navegación por rol de usuario
- ✅ Interceptores de API con manejo de errores
- ✅ Loading states y feedback visual
- ✅ Manejo de sesiones expiradas

### 📱 Diseño y Accesibilidad
- ✅ Responsive design (móvil, tablet, desktop)
- ✅ Accesibilidad WCAG 2.2 AA
- ✅ Soporte para modo reducido de movimiento
- ✅ Alto contraste automático
- ✅ Elementos focusables con tamaños mínimos (44px)
- ✅ Screen readers compatibles

## 🔧 Scripts Útiles

### Backend
```bash
# Ejecutar servidor
python manage.py runserver

# Crear nuevas migraciones
python manage.py makemigrations

# Aplicar migraciones
python manage.py migrate

# Crear superusuario
python manage.py createsuperuser

# Cargar datos de demo
python manage.py seed_demo_users

# Shell de Django
python manage.py shell
```

### Frontend
```bash
# Servidor de desarrollo
npm run dev

# Build de producción
npm run build

# Preview de build
npm run preview

# Linting
npm run lint
```

## 🎯 MVP - Criterios de Aceptación Completados

✅ **Monorepo Funcional**: Backend y frontend integrados  
✅ **Landing Page**: Hero + Características + CTAs responsive  
✅ **Autenticación Completa**: Registro, login, logout con JWT  
✅ **Roles Diferenciados**: NUTRICIONISTA y PACIENTE  
✅ **Dashboards Específicos**: Funcionalidad por tipo de usuario  
✅ **Rutas Protegidas**: Navegación segura basada en roles  
✅ **Responsive Design**: Funciona en todos los dispositivos  
✅ **Accesibilidad WCAG 2.2 AA**: Estándares de accesibilidad  
✅ **Usuarios Demo**: Credenciales de prueba funcionales  
✅ **Documentación Completa**: Guía paso a paso  
✅ **Tecnologías Solicitadas**: Django + React + Tailwind  

## 🚀 Despliegue

El proyecto está listo para despliegue en:
- **Frontend**: Vercel, Netlify, o cualquier hosting estático
- **Backend**: Heroku, Railway, DigitalOcean, o AWS
- **Base de datos**: PostgreSQL para producción

## 🎉 ¡PROYECTO LISTO!

**¡El MVP está 100% completo y funcional!**

1. **Sigue las instrucciones de instalación**
2. **Usa las credenciales de demo para probar**
3. **Explora todos los dashboards y funcionalidades**
4. **El sistema cumple con todos los requisitos**

---

🥗 **Sistema de Gestión Integral para Nutricionistas**  
🎓 **Universidad de la Cuenca del Plata**  
🤖 **Desarrollado con Claude Code**