from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from .models import User, Person, Patient, Consultation, AnthropometricMeasurement, NutritionPlan


@admin.register(User)
class UserAdmin(BaseUserAdmin):
    fieldsets = (
        (None, {'fields': ('dni', 'password')}),
        ('Información Personal', {'fields': ('first_name', 'last_name', 'email')}),
        ('Permisos', {'fields': ('role', 'is_active', 'is_staff', 'is_superuser', 'groups', 'user_permissions')}),
        ('Fechas Importantes', {'fields': ('last_login', 'date_joined')}),
    )
    add_fieldsets = (
        (None, {
            'classes': ('wide',),
            'fields': ('dni', 'first_name', 'last_name', 'email', 'role', 'password1', 'password2'),
        }),
    )
    list_display = ('dni', 'first_name', 'last_name', 'email', 'role', 'is_active')
    list_filter = ('role', 'is_active', 'is_staff', 'is_superuser')
    search_fields = ('dni', 'first_name', 'last_name', 'email')
    ordering = ('dni',)


@admin.register(Person)
class PersonAdmin(admin.ModelAdmin):
    list_display = ('user', 'phone', 'birth_date')
    search_fields = ('user__dni', 'user__first_name', 'user__last_name')


@admin.register(Patient)
class PatientAdmin(admin.ModelAdmin):
    list_display = ('person', 'has_diabetes', 'has_hypertension', 'created_at')
    list_filter = ('has_diabetes', 'has_hypertension', 'has_heart_disease', 'has_thyroid_issues')
    search_fields = ('person__user__dni', 'person__user__first_name', 'person__user__last_name')


@admin.register(Consultation)
class ConsultationAdmin(admin.ModelAdmin):
    list_display = ('patient', 'nutritionist', 'consultation_type', 'date')
    list_filter = ('consultation_type', 'date')
    search_fields = ('patient__person__user__dni', 'patient__person__user__first_name')


@admin.register(AnthropometricMeasurement)
class AnthropometricMeasurementAdmin(admin.ModelAdmin):
    list_display = ('consultation', 'weight', 'height', 'bmi', 'created_at')
    readonly_fields = ('bmi', 'waist_hip_ratio')


@admin.register(NutritionPlan)
class NutritionPlanAdmin(admin.ModelAdmin):
    list_display = ('patient', 'nutritionist', 'title', 'is_active', 'created_at')
    list_filter = ('is_active', 'created_at')
    search_fields = ('patient__person__user__dni', 'title')
