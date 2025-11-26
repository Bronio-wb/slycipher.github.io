package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.DesafioUsuario;

import java.util.List;
import java.util.Optional;

public interface DesafioUsuarioService {

    List<DesafioUsuario> findAll();

    Optional<DesafioUsuario> findById(Integer id);

    DesafioUsuario save(DesafioUsuario registro);

    void delete(Integer id);
}
