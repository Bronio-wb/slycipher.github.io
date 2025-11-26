package com.slycipher.slycipher.repository;

import com.slycipher.slycipher.entity.ProgresoUsuario;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ProgresoUsuarioRepository extends JpaRepository<ProgresoUsuario, Integer> {
}
