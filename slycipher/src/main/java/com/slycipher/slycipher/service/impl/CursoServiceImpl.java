package com.slycipher.slycipher.service.impl;

import com.slycipher.slycipher.entity.Curso;
import com.slycipher.slycipher.repository.CursoRepository;
import com.slycipher.slycipher.service.CursoService;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class CursoServiceImpl implements CursoService {

    private final CursoRepository cursoRepository;

    public CursoServiceImpl(CursoRepository cursoRepository) {
        this.cursoRepository = cursoRepository;
    }

    @Override
    public List<Curso> findAll() {
        return cursoRepository.findAll();
    }

    @Override
    public Optional<Curso> findById(Integer id) {
        return cursoRepository.findById(id);
    }

    @Override
    public Curso save(Curso curso) {
        return cursoRepository.save(curso);
    }

    @Override
    public void delete(Integer id) {
        cursoRepository.deleteById(id);
    }
}
