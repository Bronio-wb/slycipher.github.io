package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.ProgresoUsuario;
import com.slycipher.slycipher.repository.ProgresoUsuarioRepository;
import com.slycipher.slycipher.service.ProgresoUsuarioService;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class ProgresoUsuarioServiceImpl implements ProgresoUsuarioService {

    private final ProgresoUsuarioRepository progresoRepository;

    public ProgresoUsuarioServiceImpl(ProgresoUsuarioRepository progresoRepository) {
        this.progresoRepository = progresoRepository;
    }

    @Override
    public List<ProgresoUsuario> findAll() {
        return progresoRepository.findAll();
    }

    @Override
    public Optional<ProgresoUsuario> findById(Integer id) {
        return progresoRepository.findById(id);
    }

    @Override
    public ProgresoUsuario save(ProgresoUsuario progreso) {
        return progresoRepository.save(progreso);
    }

    @Override
    public void delete(Integer id) {
        progresoRepository.deleteById(id);
    }
}
