package com.slycipher.slycipher.entity;

import jakarta.persistence.*;
import java.time.LocalDateTime;

@Entity
@Table(name = "progreso_usuarios")
public class ProgresoUsuario {

    public enum Estado {
        en_progreso,
        completado
    }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "progress_id")
    private Integer id;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private Usuario usuario;

    @ManyToOne
    @JoinColumn(name = "lesson_id")
    private Leccion leccion;

    @Enumerated(EnumType.STRING)
    private Estado estado;

    @Column(name = "completado_en")
    private LocalDateTime completadoEn;

    private Float puntaje;

    public ProgresoUsuario() {}

    // GETTERS Y SETTERS
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public Usuario getUsuario() { return usuario; }
    public void setUsuario(Usuario usuario) { this.usuario = usuario; }

    public Leccion getLeccion() { return leccion; }
    public void setLeccion(Leccion leccion) { this.leccion = leccion; }

    public Estado getEstado() { return estado; }
    public void setEstado(Estado estado) { this.estado = estado; }

    public LocalDateTime getCompletadoEn() { return completadoEn; }
    public void setCompletadoEn(LocalDateTime completadoEn) { this.completadoEn = completadoEn; }

    public Float getPuntaje() { return puntaje; }
    public void setPuntaje(Float puntaje) { this.puntaje = puntaje; }
}
