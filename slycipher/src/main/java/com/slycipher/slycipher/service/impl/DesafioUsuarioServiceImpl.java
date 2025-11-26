package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.DesafioUsuario;
import com.slycipher.slycipher.repository.DesafioUsuarioRepository;
import com.slycipher.slycipher.service.DesafioUsuarioService;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class DesafioUsuarioServiceImpl implements DesafioUsuarioService {

    private final DesafioUsuarioRepository desafioUsuarioRepository;

    public DesafioUsuarioServiceImpl(DesafioUsuarioRepository desafioUsuarioRepository) {
        this.desafioUsuarioRepository = desafioUsuarioRepository;
    }

    @Override
    public List<DesafioUsuario> findAll() {
        return desafioUsuarioRepository.findAll();
    }

    @Override
    public Optional<DesafioUsuario> findById(Integer id) {
        return desafioUsuarioRepository.findById(id);
    }

    @Override
    public DesafioUsuario save(DesafioUsuario registro) {
        return desafioUsuarioRepository.save(registro);
    }

    @Override
    public void delete(Integer id) {
        desafioUsuarioRepository.deleteById(id);
    }
}
