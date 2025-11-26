package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Usuario;

import java.util.List;
import java.util.Optional;

public interface UsuarioService {

    List<Usuario> findAll();

    Optional<Usuario> findById(Integer id);

    Optional<Usuario> findByEmail(String email);

    Usuario save(Usuario usuario);

    void delete(Integer id);
}
