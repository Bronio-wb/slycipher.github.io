package com.slycipher.slycipher.entity;

import jakarta.persistence.*;
import java.time.LocalDateTime;

@Entity
@Table(name = "logros")
public class Logro {

    public enum Tipo {
        lecciones,
        desafios,
        racha,
        especial
    }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "achievement_id")
    private Long id;

    private String nombre;

    private String descripcion;

    private String icono;

    @Column(name = "puntos_requeridos")
    private Integer puntosRequeridos;

    @Enumerated(EnumType.STRING)
    private Tipo tipo;

    private Boolean activo;

    @Column(name = "fecha_creacion")
    private LocalDateTime fechaCreacion;

    public Logro() {}

    // GETTERS Y SETTERS
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public String getNombre() { return nombre; }
    public void setNombre(String nombre) { this.nombre = nombre; }

    public String getDescripcion() { return descripcion; }
    public void setDescripcion(String descripcion) { this.descripcion = descripcion; }

    public String getIcono() { return icono; }
    public void setIcono(String icono) { this.icono = icono; }

    public Integer getPuntosRequeridos() { return puntosRequeridos; }
    public void setPuntosRequeridos(Integer puntosRequeridos) { this.puntosRequeridos = puntosRequeridos; }

    public Tipo getTipo() { return tipo; }
    public void setTipo(Tipo tipo) { this.tipo = tipo; }

    public Boolean getActivo() { return activo; }
    public void setActivo(Boolean activo) { this.activo = activo; }

    public LocalDateTime getFechaCreacion() { return fechaCreacion; }
    public void setFechaCreacion(LocalDateTime fechaCreacion) { this.fechaCreacion = fechaCreacion; }
}
