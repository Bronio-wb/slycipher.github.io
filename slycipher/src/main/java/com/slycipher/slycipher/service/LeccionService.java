package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Leccion;

import java.util.List;
import java.util.Optional;

public interface LeccionService {

    List<Leccion> findAll();

    Optional<Leccion> findById(Integer id);

    Leccion save(Leccion leccion);

    void delete(Integer id);
}
