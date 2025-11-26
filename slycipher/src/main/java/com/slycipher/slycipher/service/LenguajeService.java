package com.slycipher.slycipher.service;

import com.slycipher.slycipher.entity.Lenguaje;
import java.util.List;

public interface LenguajeService {

    List<Lenguaje> findAll();

    Lenguaje findById(Integer id);

    Lenguaje save(Lenguaje lenguaje);

    void delete(Integer id);
}
