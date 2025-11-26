package com.slycipher.slycipher.entity;

import jakarta.persistence.*;

@Entity
@Table(name = "desafios")
public class Desafio {

    public enum Dificultad {
        facil,
        medio,
        dificil
    }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "challenge_id")
    private Integer id;

    @ManyToOne
    @JoinColumn(name = "course_id")
    private Curso curso;

    private String titulo;

    private String descripcion;

    @Enumerated(EnumType.STRING)
    private Dificultad dificultad;

    private String solucion;

    @ManyToOne
    @JoinColumn(name = "language_id")
    private Lenguaje lenguaje;

    public Desafio() {}

    // GETTERS Y SETTERS
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public Curso getCurso() { return curso; }
    public void setCurso(Curso curso) { this.curso = curso; }

    public String getTitulo() { return titulo; }
    public void setTitulo(String titulo) { this.titulo = titulo; }

    public String getDescripcion() { return descripcion; }
    public void setDescripcion(String descripcion) { this.descripcion = descripcion; }

    public Dificultad getDificultad() { return dificultad; }
    public void setDificultad(Dificultad dificultad) { this.dificultad = dificultad; }

    public String getSolucion() { return solucion; }
    public void setSolucion(String solucion) { this.solucion = solucion; }

    public Lenguaje getLenguaje() { return lenguaje; }
    public void setLenguaje(Lenguaje lenguaje) { this.lenguaje = lenguaje; }
}
