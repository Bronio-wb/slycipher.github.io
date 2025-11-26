package com.slycipher.slycipher.entity;

import jakarta.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "cursos")
public class Curso {

    public enum Nivel {
        principiante,
        intermedio,
        avanzado
    }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "course_id")
    private Integer id;

    private String titulo;

    private String descripcion;

    @Enumerated(EnumType.STRING)
    private Nivel nivel;

    @ManyToOne
    @JoinColumn(name = "language_id")
    private Lenguaje lenguaje;

    @ManyToOne
    @JoinColumn(name = "category_id")
    private Categoria categoria;

    @ManyToOne
    @JoinColumn(name = "creado_por")
    private Usuario creador;

    private Boolean estado;

    @Column(name = "duracion_estimada")
    private Integer duracionEstimada;

    private BigDecimal precio;

    private String requisitos;

    @Column(name = "fecha_creacion")
    private LocalDateTime fechaCreacion;

    public Curso() {}

    // GETTERS Y SETTERS
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public String getTitulo() { return titulo; }
    public void setTitulo(String titulo) { this.titulo = titulo; }

    public String getDescripcion() { return descripcion; }
    public void setDescripcion(String descripcion) { this.descripcion = descripcion; }

    public Nivel getNivel() { return nivel; }
    public void setNivel(Nivel nivel) { this.nivel = nivel; }

    public Lenguaje getLenguaje() { return lenguaje; }
    public void setLenguaje(Lenguaje lenguaje) { this.lenguaje = lenguaje; }

    public Categoria getCategoria() { return categoria; }
    public void setCategoria(Categoria categoria) { this.categoria = categoria; }

    public Usuario getCreador() { return creador; }
    public void setCreador(Usuario creador) { this.creador = creador; }

    public Boolean getEstado() { return estado; }
    public void setEstado(Boolean estado) { this.estado = estado; }

    public Integer getDuracionEstimada() { return duracionEstimada; }
    public void setDuracionEstimada(Integer duracionEstimada) { this.duracionEstimada = duracionEstimada; }

    public BigDecimal getPrecio() { return precio; }
    public void setPrecio(BigDecimal precio) { this.precio = precio; }

    public String getRequisitos() { return requisitos; }
    public void setRequisitos(String requisitos) { this.requisitos = requisitos; }

    public LocalDateTime getFechaCreacion() { return fechaCreacion; }
    public void setFechaCreacion(LocalDateTime fechaCreacion) { this.fechaCreacion = fechaCreacion; }
}
