package com.slycipher.slycipher.repository;

import com.slycipher.slycipher.entity.DesafioUsuario;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface DesafioUsuarioRepository extends JpaRepository<DesafioUsuario, Integer> {
}
