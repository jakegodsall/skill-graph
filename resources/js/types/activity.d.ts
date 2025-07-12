interface Activity {
  id: number;
  name: string;
  description?: string;
  type: 'course' | 'project' | 'book' | 'practice' | 'certification' | 'other';
  status: 'not_started' | 'in_progress' | 'completed' | 'paused';
  url?: string;
  position_x?: number;
  position_y?: number;
  skill_id: number;
  skill: Skill;
  depends_on: Activity[];
  required_by: Activity[];
}