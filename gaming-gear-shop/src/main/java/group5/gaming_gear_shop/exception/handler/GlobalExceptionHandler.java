package group5.gaming_gear_shop.exception.handler;


import group5.gaming_gear_shop.dto.ApiResponse;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;

@ControllerAdvice
public class GlobalExceptionHandler {
    @ExceptionHandler({
        RuntimeException.class,
    })
    ResponseEntity<ApiResponse<?>> handleCustomExceptions(RuntimeException ex) {
        if (ex instanceof BaseCustomException customEx) {
            ErrorCode errorCode = customEx.getErrorCode();
            return ResponseEntity.badRequest()
                    .body(ApiResponse.error(errorCode.getCode(), errorCode.getMessage()));
        }

        // Handle unexpected exceptions gracefully
        return ResponseEntity.status(500)
                .body(ApiResponse.error(500, "Internal Server Error: " + ex.getMessage()));
    }
}
