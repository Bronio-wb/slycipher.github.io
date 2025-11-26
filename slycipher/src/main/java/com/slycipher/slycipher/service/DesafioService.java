package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Desafio;

import java.util.List;
import java.util.Optional;

public interface DesafioService {

    List<Desafio> findAll();

    Optional<Desafio> findById(Integer id);

    Desafio save(Desafio desafio);

    void delete(Integer id);

}
