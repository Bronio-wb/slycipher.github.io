package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.LogroUsuario;

import java.util.List;
import java.util.Optional;

public interface LogroUsuarioService {

    List<LogroUsuario> findAll();

    Optional<LogroUsuario> findById(Long id);

    LogroUsuario save(LogroUsuario logroUsuario);

    void delete(Long id);
}
