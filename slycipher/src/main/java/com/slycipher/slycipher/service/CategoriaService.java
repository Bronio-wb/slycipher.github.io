package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Categoria;

import java.util.List;

public interface CategoriaService {

    List<Categoria> findAll();

    Categoria findById(Integer id);

    Categoria save(Categoria categoria);

    void delete(Integer id);
}
