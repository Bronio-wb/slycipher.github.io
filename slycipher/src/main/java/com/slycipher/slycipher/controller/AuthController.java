package com.slycipher.slycipher.controller;

import com.slycipher.slycipher.entity.Usuario;
import com.slycipher.slycipher.service.UsuarioService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/auth")
public class AuthController {

    private final UsuarioService usuarioService;

    public AuthController(UsuarioService usuarioService) {
        this.usuarioService = usuarioService;
    }

    // Mostrar login
    @GetMapping("/login")
    public String login(Model model) {
        model.addAttribute("usuario", new Usuario());
        return "auth/login"; 
    }

    // Mostrar registro
    @GetMapping("/register")
    public String register(Model model) {
        model.addAttribute("usuario", new Usuario());
        return "auth/register";
    }

    // Guardar el registro
    @PostMapping("/register")
    public String registerUser(@ModelAttribute Usuario usuario) {

        usuarioService.save(usuario);

        return "redirect:/auth/login";
    }

    @GetMapping("/logout")
    public String logout() {
        return "redirect:/auth/login";
    }
}
