from django.contrib import admin
from django.urls import path, re_path, include
from django.views.generic import TemplateView
from django.conf.urls.static import static
from django.conf import settings

urlpatterns = [
    path('admin/', admin.site.urls),
    path('api/', include('apps.users.urls')),
] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)

# NO capture rutas de estáticos
urlpatterns += [
    re_path(r'^(?!admin/|api/|static/|media/).*$',
        TemplateView.as_view(template_name='index.html')),

]
