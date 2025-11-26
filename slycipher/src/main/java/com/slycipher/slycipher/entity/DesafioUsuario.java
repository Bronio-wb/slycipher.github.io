package com.slycipher.slycipher.entity;

import jakarta.persistence.*;
import java.time.LocalDateTime;

@Entity
@Table(name = "desafios_usuarios")
public class DesafioUsuario {

    public enum Estado {
        intentado,
        completado
    }

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "user_challenge_id")
    private Integer id;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private Usuario usuario;

    @ManyToOne
    @JoinColumn(name = "challenge_id")
    private Desafio desafio;

    @Enumerated(EnumType.STRING)
    private Estado estado;

    private String envio;

    @Column(name = "completado_en")
    private LocalDateTime completadoEn;

    private Float puntaje;

    public DesafioUsuario() {}

    // GETTERS Y SETTERS
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public Usuario getUsuario() { return usuario; }
    public void setUsuario(Usuario usuario) { this.usuario = usuario; }

    public Desafio getDesafio() { return desafio; }
    public void setDesafio(Desafio desafio) { this.desafio = desafio; }

    public Estado getEstado() { return estado; }
    public void setEstado(Estado estado) { this.estado = estado; }

    public String getEnvio() { return envio; }
    public void setEnvio(String envio) { this.envio = envio; }

    public LocalDateTime getCompletadoEn() { return completadoEn; }
    public void setCompletadoEn(LocalDateTime completadoEn) { this.completadoEn = completadoEn; }

    public Float getPuntaje() { return puntaje; }
    public void setPuntaje(Float puntaje) { this.puntaje = puntaje; }
}
