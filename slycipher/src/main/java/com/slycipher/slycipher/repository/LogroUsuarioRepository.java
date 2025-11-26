package com.slycipher.slycipher.repository;

import com.slycipher.slycipher.entity.LogroUsuario;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface LogroUsuarioRepository extends JpaRepository<LogroUsuario, Long> {
}
