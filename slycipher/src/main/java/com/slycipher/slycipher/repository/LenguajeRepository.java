package com.slycipher.slycipher.repository;

import com.slycipher.slycipher.entity.Lenguaje;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface LenguajeRepository extends JpaRepository<Lenguaje, Integer> {
}
