package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.Desafio;
import com.slycipher.slycipher.repository.DesafioRepository;
import com.slycipher.slycipher.service.DesafioService;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class DesafioServiceImpl implements DesafioService {

    private final DesafioRepository desafioRepository;

    public DesafioServiceImpl(DesafioRepository desafioRepository) {
        this.desafioRepository = desafioRepository;
    }

    @Override
    public List<Desafio> findAll() {
        return desafioRepository.findAll();
    }

    @Override
    public Optional<Desafio> findById(Integer id) {
        return desafioRepository.findById(id);
    }

    @Override
    public Desafio save(Desafio desafio) {
        return desafioRepository.save(desafio);
    }

    @Override
    public void delete(Integer id) {
        desafioRepository.deleteById(id);
    }
}
