package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.Lenguaje;
import com.slycipher.slycipher.repository.LenguajeRepository;
import com.slycipher.slycipher.service.LenguajeService;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class LenguajeServiceImpl implements LenguajeService {

    private final LenguajeRepository lenguajeRepository;

    public LenguajeServiceImpl(LenguajeRepository lenguajeRepository) {
        this.lenguajeRepository = lenguajeRepository;
    }

    @Override
    public List<Lenguaje> findAll() {
        return lenguajeRepository.findAll();
    }

    @Override
    public Lenguaje findById(Integer id) {
        return lenguajeRepository.findById(id).orElse(null);
    }

    @Override
    public Lenguaje save(Lenguaje lenguaje) {
        return lenguajeRepository.save(lenguaje);
    }

    @Override
    public void delete(Integer id) {
        lenguajeRepository.deleteById(id);
    }
}
