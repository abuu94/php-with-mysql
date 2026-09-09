ALTER TABLE image_reactions
    ADD UNIQUE KEY uq_image_reactions_student_image (student_id, image_id);