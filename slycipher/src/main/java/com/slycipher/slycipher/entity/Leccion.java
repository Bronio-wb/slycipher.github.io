package com.slycipher.slycipher.entity;

import jakarta.persistence.*;

@Entity
@Table(name = "lecciones")
public class Leccion {

    public enum Estado {
        pendiente,
        aprobada,
        rechazada
    }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "lesson_id")
    private Integer id;

    @ManyToOne
    @JoinColumn(name = "course_id")
    private Curso curso;

    private String titulo;

    private String contenido;

    private Integer orden;

    @Enumerated(EnumType.STRING)
    private Estado estado;

    public Leccion() {}

    // GETTERS Y SETTERS
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public Curso getCurso() { return curso; }
    public void setCurso(Curso curso) { this.curso = curso; }

    public String getTitulo() { return titulo; }
    public void setTitulo(String titulo) { this.titulo = titulo; }

    public String getContenido() { return contenido; }
    public void setContenido(String contenido) { this.contenido = contenido; }

    public Integer getOrden() { return orden; }
    public void setOrden(Integer orden) { this.orden = orden; }

    public Estado getEstado() { return estado; }
    public void setEstado(Estado estado) { this.estado = estado; }
}
