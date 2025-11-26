package com.slycipher.slycipher.repository;

import com.slycipher.slycipher.entity.Desafio;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface DesafioRepository extends JpaRepository<Desafio, Integer> {
}
