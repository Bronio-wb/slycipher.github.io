package com.slycipher.slycipher.entity;

import jakarta.persistence.*;

@Entity
@Table(name = "lenguajes")
public class Lenguaje {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "language_id")
    private Integer id;

    private String nombre;

    private String descripcion;

    public Lenguaje() {}

    // Getters y setters
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }

    public String getNombre() { return nombre; }
    public void setNombre(String nombre) { this.nombre = nombre; }

    public String getDescripcion() { return descripcion; }
    public void setDescripcion(String descripcion) { this.descripcion = descripcion; }
}
