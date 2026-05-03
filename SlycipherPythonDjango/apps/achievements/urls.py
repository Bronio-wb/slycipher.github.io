from django.urls import path

from . import views


urlpatterns = [
	path('student/logros/', views.student_achievements, name='student_achievements'),
	path('student/achievements/', views.student_achievements),
]
