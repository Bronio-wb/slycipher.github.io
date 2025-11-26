package com.slycipher.slycipher.repository;

import com.slycipher.slycipher.entity.Leccion;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface LeccionRepository extends JpaRepository<Leccion, Integer> {
}
