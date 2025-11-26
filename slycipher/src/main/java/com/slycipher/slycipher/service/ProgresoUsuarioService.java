package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.ProgresoUsuario;

import java.util.List;
import java.util.Optional;

public interface ProgresoUsuarioService {

    List<ProgresoUsuario> findAll();

    Optional<ProgresoUsuario> findById(Integer id);

    ProgresoUsuario save(ProgresoUsuario progreso);

    void delete(Integer id);
}
