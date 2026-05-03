from django.urls import path

from . import views


urlpatterns = [
	path('student/desafios/', views.student_challenges, name='student_challenges'),
	path('student/challenges/', views.student_challenges),
	path('student/desafios/ejecutar/', views.student_code_execute, name='student_code_execute'),
	path('student/desafios/<int:pk>/', views.student_challenge_detail, name='student_challenge_detail'),
	path('student/desafios/<int:pk>/intentar/', views.student_challenge_attempt, name='student_challenge_attempt'),
	path('student/desafios/<int:pk>/enviar/', views.student_challenge_submit, name='student_challenge_submit'),
	path('developer/challenges/', views.developer_challenges, name='developer_challenges'),
	path('developer/challenges/create/', views.developer_challenge_create, name='developer_challenge_create'),
	path('developer/challenges/<int:challenge_id>/view/', views.developer_challenge_view, name='developer_challenge_view'),
	path('developer/challenges/<int:challenge_id>/edit/', views.developer_challenge_edit, name='developer_challenge_edit'),
	path('developer/challenges/<int:challenge_id>/delete/', views.developer_challenge_delete, name='developer_challenge_delete'),
	path('developer/solutions/pending/', views.developer_pending_solutions, name='developer_pending_solutions'),
	path('developer/solutions/<int:submission_id>/review/', views.developer_review_solution, name='developer_review_solution'),
	path('developer/solutions/<int:submission_id>/evaluate/', views.developer_evaluate_solution, name='developer_evaluate_solution'),
]
