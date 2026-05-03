from django.urls import include, path

from . import views

app_name = 'core'

urlpatterns = [
    path('', views.home_view, name='home'),
    path('home/', views.home_view, name='home_alias'),
    path('login/', views.login_view, name='login'),
    path('login/google/', views.oauth_google_start, name='oauth_google_start'),
    path('login/google/callback/', views.oauth_google_callback, name='oauth_google_callback'),
    path('login/github/', views.oauth_github_start, name='oauth_github_start'),
    path('login/github/callback/', views.oauth_github_callback, name='oauth_github_callback'),
    path('login/microsoft/', views.oauth_microsoft_start, name='oauth_microsoft_start'),
    path('login/microsoft/callback/', views.oauth_microsoft_callback, name='oauth_microsoft_callback'),
    # Aliases de compatibilidad (patrón común de allauth)
    path('accounts/google/login/', views.oauth_google_start, name='oauth_google_start_alias'),
    path('accounts/google/login/callback/', views.oauth_google_callback, name='oauth_google_callback_alias'),
    path('accounts/github/login/', views.oauth_github_start, name='oauth_github_start_alias'),
    path('accounts/github/login/callback/', views.oauth_github_callback, name='oauth_github_callback_alias'),
    path('accounts/microsoft/login/', views.oauth_microsoft_start, name='oauth_microsoft_start_alias'),
    path('accounts/microsoft/login/callback/', views.oauth_microsoft_callback, name='oauth_microsoft_callback_alias'),
    path('logout/', views.logout_view, name='logout'),
    path('register/', views.register_view, name='register'),
    path('profile/', views.profile_view, name='profile'),
    path('certificates/verify/<str:code>/', views.certificate_verify, name='certificate_verify'),
    path('dashboard/', views.admin_dashboard, name='admin_dashboard'),
    path('data-load/', views.admin_data_load, name='admin_data_load'),
    path('certifications/', views.admin_certifications, name='admin_certifications'),
    path('dashboard/certificates/<int:user_id>/pdf/', views.admin_student_certificate_pdf, name='admin_student_certificate_pdf'),
    path('dashboard/certificates/<int:user_id>/courses/<int:course_id>/pdf/', views.admin_student_course_certificate_pdf, name='admin_student_course_certificate_pdf'),
    path('statistics/', views.admin_statistics, name='admin_statistics'),
    path('statistics/pdf/', views.admin_statistics_pdf, name='admin_statistics_pdf'),
    path('reports/', views.admin_reports, name='admin_reports'),
    path('reports/result/', views.admin_reports_result, name='admin_reports_result'),
    path('users/', views.users_list, name='users'),
    path('users/new/', views.user_create, name='user_create'),
    path('users/<int:pk>/edit/', views.user_edit, name='user_edit'),
    path('users/<int:pk>/delete/', views.user_delete, name='user_delete'),
    path('users/<int:pk>/toggle_active/', views.user_toggle_active, name='user_toggle_active'),
    path('', include('apps.courses.urls')),
    path('', include('apps.challenges.urls')),
    path('', include('apps.achievements.urls')),
]
