package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.Usuario;
import com.slycipher.slycipher.service.UsuarioService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/usuarios")
public class UsuarioController {

    private final UsuarioService usuarioService;

    public UsuarioController(UsuarioService usuarioService) {
        this.usuarioService = usuarioService;
    }

    // Mostrar lista de usuarios
    @GetMapping
    public String listarUsuarios(Model model) {
        model.addAttribute("usuarios", usuarioService.findAll());
        return "usuarios/lista"; // luego crearemos la vista
    }

    // Crear nuevo usuario
    @GetMapping("/crear")
    public String crearUsuario(Model model) {
        model.addAttribute("usuario", new Usuario());
        return "usuarios/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute Usuario usuario) {
        usuarioService.save(usuario);
        return "redirect:/usuarios";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Integer id, Model model) {
        Usuario usuario = usuarioService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID no válido: " + id));

        model.addAttribute("usuario", usuario);
        return "usuarios/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Integer id) {
        usuarioService.delete(id);
        return "redirect:/usuarios";
    }
}
