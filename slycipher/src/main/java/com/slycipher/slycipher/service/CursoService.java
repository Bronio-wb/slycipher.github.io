package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Curso;

import java.util.List;
import java.util.Optional;

public interface CursoService {

    List<Curso> findAll();

    Optional<Curso> findById(Integer id);

    Curso save(Curso curso);

    void delete(Integer id);
}
