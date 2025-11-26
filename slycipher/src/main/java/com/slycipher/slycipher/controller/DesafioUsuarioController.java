package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.DesafioUsuario;
import com.slycipher.slycipher.service.DesafioUsuarioService;
import com.slycipher.slycipher.service.UsuarioService;
import com.slycipher.slycipher.service.DesafioService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/desafios-usuarios")
public class DesafioUsuarioController {

    private final DesafioUsuarioService desafioUsuarioService;
    private final UsuarioService usuarioService;
    private final DesafioService desafioService;

    public DesafioUsuarioController(DesafioUsuarioService desafioUsuarioService,
            UsuarioService usuarioService,
            DesafioService desafioService) {
        this.desafioUsuarioService = desafioUsuarioService;
        this.usuarioService = usuarioService;
        this.desafioService = desafioService;
    }

    @GetMapping
    public String listar(Model model) {
        model.addAttribute("desafiosUsuarios", desafioUsuarioService.findAll());
        return "desafiosUsuarios/lista";
    }

    @GetMapping("/crear")
    public String crear(Model model) {
        model.addAttribute("desafioUsuario", new DesafioUsuario());
        model.addAttribute("usuarios", usuarioService.findAll());
        model.addAttribute("desafios", desafioService.findAll());
        return "desafiosUsuarios/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute DesafioUsuario desafioUsuario) {
        desafioUsuarioService.save(desafioUsuario);
        return "redirect:/desafios-usuarios";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Integer id, Model model) {
        DesafioUsuario desafioUsuario = desafioUsuarioService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido " + id));

        model.addAttribute("desafioUsuario", desafioUsuario);
        model.addAttribute("usuarios", usuarioService.findAll());
        model.addAttribute("desafios", desafioService.findAll());
        return "desafiosUsuarios/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Integer id) {
        desafioUsuarioService.delete(id);
        return "redirect:/desafios-usuarios";
    }
}
