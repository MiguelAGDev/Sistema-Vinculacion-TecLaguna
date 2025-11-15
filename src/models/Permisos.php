<?php

/* 13 de Noviembre 13:02 PM

Clase permisos, contienen todos los permisos
que se iniciaran en la base de datos
*/ 


class Permisos{
    /* Permisos Basicos del Sistema (bits 0 - 15) */    
    const PERMISO_INICIAR_SESION            = 1;                    // 2^0
    const PERMISO_CAMBIAR_CONTRASENA        = 2;                    // 2^1
    const PERMISO_VER_PERFIL                = 4;                    // 2^2
    const PERMISO_EDITAR_PERFIL             = 8;                    // 2^3
    const PERMISO_SUBIR_DOCUMENTO_CV        = 16;                   // 2^4
    const PERMISO_DESCARGAR_DOCUMENTO       = 32;                   // 2^5

    /* PERMISO DE CONVOCATORIA (bits 16-23) */
    const PERMISO_VER_CONVOCATORIAS           = 65536;              // 2^16
    const PERMISO_REGISTRARSE_CONVOCATORIA    = 131072;             // 2^17
    const PERMISO_CREAR_CONVOCATORIA          = 262144;             // 2^18
    const PERMISO_EDITAR_CONVOCATORIA         = 524288;             // 2^19
    const PERMISO_ELIMINAR_CONVOCATORIA       = 1048576;            // 2^20
    const PERMISO_PUBLICAR_CONVOCATORIA       = 2097152;            // 2^21

    /* PERMISO DE EMPRESA (CRUD ADMIN) (bits 24-28) */
    const PERMISO_VER_EMPRESAS                 = 16777216;          // 2^24
   // const PERMISO_CREAR_EMPRESA                = 33554432;  // 2^25
    const PERMISO_VALIDAR_EMPRESA               = 33554432;         // 2^25
    const PERMISO_EDITAR_EMPRESA                = 67108864;         // 2^26
    const PERMISO_ELIMINAR_EMPRESA              = 134217728;        // 2^27
    const  PERMISO_SUBIR_FLYER                  = 268435456;        // 2^28


    /* PERMISO DE RESIDENTES Y EGRESADOS (bits 32 - 38) */
    const PERMISO_SUBIR_PROYECTO_RESIDENTE      = 536870912;        // 2^32
    const PERMISO_VER_PROYECTOS                 = 1073741824;       // 2^33
    const PERMISOS_EVALUAR_PROYECTOS            = 2147483648;       // 2^34
    const PERMISO_VER_EGRESADO                  = 4294967296;       // 2^35
    const PERMISO_EDITAR_EGRESADO               = 8589934592;       // 2^36
    const PERMISO_VER_POSTULACIONES             = 17179869184;      // 2^37
    const PERMISO_VER_ESTADISTICAS              = 34359738368;      // 2^38 

    
    public static function verificarPermiso ($permisos_Usuario,$permiso){
        if(((int)$permisos_Usuario & (int)$permiso) === (int)$permiso){
            return true;
        }
            return false;  
    }
    public static function agregarPermiso ($permiso,$id_usuario,$nuevoPermiso){
        if(((int)$permisos_Usuario | (int)$permiso) === (int)$permiso){
            return true;
        }
            return false; 
    }
}

