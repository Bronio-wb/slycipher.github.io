package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.LogroUsuario;
import com.slycipher.slycipher.service.LogroUsuarioService;
import org.springframework.stereotype.Service;
import com.slycipher.slycipher.repository.LogroUsuarioRepository;

import java.util.List;
import java.util.Optional;

@Service
public class LogroUsuarioServiceImpl implements LogroUsuarioService {

    private final LogroUsuarioRepository logroUsuarioRepository;

    public LogroUsuarioServiceImpl(LogroUsuarioRepository logroUsuarioRepository) {
        this.logroUsuarioRepository = logroUsuarioRepository;
    }

    @Override
    public List<LogroUsuario> findAll() {
        return logroUsuarioRepository.findAll();
    }

    @Override
    public Optional<LogroUsuario> findById(Long id) {
        return logroUsuarioRepository.findById(id);
    }

    @Override
    public LogroUsuario save(LogroUsuario logroUsuario) {
        return logroUsuarioRepository.save(logroUsuario);
    }

    @Override
    public void delete(Long id) {
        logroUsuarioRepository.deleteById(id);
    }
}
