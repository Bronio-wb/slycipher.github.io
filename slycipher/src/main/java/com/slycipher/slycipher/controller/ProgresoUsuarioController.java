package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.ProgresoUsuario;
import com.slycipher.slycipher.service.ProgresoUsuarioService;
import com.slycipher.slycipher.service.LeccionService;
import com.slycipher.slycipher.service.UsuarioService;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/progreso")
public class ProgresoUsuarioController {

    private final ProgresoUsuarioService progresoService;
    private final UsuarioService usuarioService;
    private final LeccionService leccionService;

    public ProgresoUsuarioController(ProgresoUsuarioService progresoService,
                                     UsuarioService usuarioService,
                                     LeccionService leccionService) {
        this.progresoService = progresoService;
        this.usuarioService = usuarioService;
        this.leccionService = leccionService;
    }

    @GetMapping
    public String listar(Model model) {
        model.addAttribute("progresos", progresoService.findAll());
        return "progreso/lista";
    }

    @GetMapping("/crear")
    public String crear(Model model) {
        model.addAttribute("progreso", new ProgresoUsuario());
        model.addAttribute("usuarios", usuarioService.findAll());
        model.addAttribute("lecciones", leccionService.findAll());
        return "progreso/crear";
    }

    @PostMapping("/guardar")
    public String guardar(@ModelAttribute ProgresoUsuario progreso) {
        progresoService.save(progreso);
        return "redirect:/progreso";
    }

    @GetMapping("/editar/{id}")
    public String editar(@PathVariable Integer id, Model model) {
        ProgresoUsuario progreso = progresoService.findById(id)
                .orElseThrow(() -> new IllegalArgumentException("ID inválido " + id));

        model.addAttribute("progreso", progreso);
        model.addAttribute("usuarios", usuarioService.findAll());
        model.addAttribute("lecciones", leccionService.findAll());
        return "progreso/editar";
    }

    @GetMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Integer id) {
        progresoService.delete(id);
        return "redirect:/progreso";
    }
}
