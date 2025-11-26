package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.Logro;
import com.slycipher.slycipher.repository.LogroRepository;
import com.slycipher.slycipher.service.LogroService;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class LogroServiceImpl implements LogroService {

    private final LogroRepository logroRepository;

    public LogroServiceImpl(LogroRepository logroRepository) {
        this.logroRepository = logroRepository;
    }

    @Override
    public List<Logro> findAll() {
        return logroRepository.findAll();
    }

    @Override
    public Optional<Logro> findById(Long id) {
        return logroRepository.findById(id);
    }

    @Override
    public Logro save(Logro logro) {
        return logroRepository.save(logro);
    }

    @Override
    public void delete(Long id) {
        logroRepository.deleteById(id);
    }
}
