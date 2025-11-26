package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Logro;

import java.util.List;
import java.util.Optional;

public interface LogroService {

    List<Logro> findAll();

    Optional<Logro> findById(Long id);

    Logro save(Logro logro);

    void delete(Long id);
}
