package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.Leccion;
import com.slycipher.slycipher.repository.LeccionRepository;
import com.slycipher.slycipher.service.LeccionService;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class LeccionServiceImpl implements LeccionService {

    private final LeccionRepository leccionRepository;

    public LeccionServiceImpl(LeccionRepository leccionRepository) {
        this.leccionRepository = leccionRepository;
    }

    @Override
    public List<Leccion> findAll() {
        return leccionRepository.findAll();
    }

    @Override
    public Optional<Leccion> findById(Integer id) {
        return leccionRepository.findById(id);
    }

    @Override
    public Leccion save(Leccion leccion) {
        return leccionRepository.save(leccion);
    }

    @Override
    public void delete(Integer id) {
        leccionRepository.deleteById(id);
    }
}
