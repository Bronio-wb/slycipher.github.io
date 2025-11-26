package com.slycipher.slycipher.entity;

import jakarta.persistence.*;
import java.time.LocalDateTime;

@Entity
@Table(name = "logros_usuarios")
public class LogroUsuario {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "user_achievement_id")
    private Long id;

    @ManyToOne
    @JoinColumn(name = "user_id")
    private Usuario usuario;

    @ManyToOne
    @JoinColumn(name = "achievement_id")
    private Logro logro;

    @Column(name = "desbloqueado_en")
    private LocalDateTime desbloqueadoEn;

    public LogroUsuario() {}

    // GETTERS Y SETTERS
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public Usuario getUsuario() { return usuario; }
    public void setUsuario(Usuario usuario) { this.usuario = usuario; }

    public Logro getLogro() { return logro; }
    public void setLogro(Logro logro) { this.logro = logro; }

    public LocalDateTime getDesbloqueadoEn() { return desbloqueadoEn; }
    public void setDesbloqueadoEn(LocalDateTime desbloqueadoEn) { this.desbloqueadoEn = desbloqueadoEn; }
}
