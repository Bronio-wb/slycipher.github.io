package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.LogroUsuario;
import com.slycipher.slycipher.service.LogroUsuarioService;
import com.slycipher.slycipher.service.UsuarioService;
import com.slycipher.slycipher.service.LogroService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/logros-usuarios")
public class LogroUsuarioController {

    private final LogroUsuarioService logroUsuarioService;
    private final UsuarioService usuarioService;
    private final LogroService logroService;

    public LogroUsuarioController(LogroUsuarioService logroUsuarioService,
            UsuarioService usuarioService,
            LogroService logroService) {
        this.logroUsuarioService = logroUsuarioService;
        this.usuarioService = usuarioService;
        this.logroService = logroService;
    }

    @GetMapping
    public String listar(Model model) {
        model.addAttribute("logrosUsuarios", logroUsuarioService.findAll());
        return "logrosUsuarios/lista";
    }

    @GetMapping("/crear")
    public String crear(Model model) {
        model.addAttribute("logroUsuario", new LogroUsuario());
        model.addAttribute("usuarios", usuarioService.findAll());
        model.addAttribute("logros", logroService.findAll());
        return "logrosUsuarios/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute LogroUsuario logroUsuario) {
        logroUsuarioService.save(logroUsuario);
        return "redirect:/logros-usuarios";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Long id, Model model) {
        LogroUsuario logroUsuario = logroUsuarioService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido " + id));

        model.addAttribute("logroUsuario", logroUsuario);
        model.addAttribute("usuarios", usuarioService.findAll());
        model.addAttribute("logros", logroService.findAll());
        return "logrosUsuarios/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Long id) {
        logroUsuarioService.delete(id);
        return "redirect:/logros-usuarios";
    }
}
