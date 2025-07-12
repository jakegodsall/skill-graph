import React, { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Plus, Edit, Trash2, BookOpen, Code, Award, FileText, Target, Zap, ExternalLink } from 'lucide-react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Skill {
  id: number;
  name: string;
  color: string;
}

interface Activity {
  id: number;
  name: string;
  description?: string;
  type: 'course' | 'project' | 'book' | 'practice' | 'certification' | 'other';
  status: 'not_started' | 'in_progress' | 'completed' | 'paused';
  url?: string;
  estimated_hours?: number;
  actual_hours?: number;
  skill_id: number;
  skill: Skill;
  depends_on: Activity[];
  required_by: Activity[];
  created_at: string;
  updated_at: string;
}

interface ActivityFormData {
  name: string;
  description: string;
  type: 'course' | 'project' | 'book' | 'practice' | 'certification' | 'other';
  status: 'not_started' | 'in_progress' | 'completed' | 'paused';
  url: string;
  estimated_hours: string;
  actual_hours: string;
  skill_id: string;
  dependencies: string[];
}

const activityTypes = [
  { value: 'course', label: 'Course', icon: BookOpen, color: 'text-blue-600' },
  { value: 'project', label: 'Project', icon: Code, color: 'text-green-600' },
  { value: 'book', label: 'Book', icon: FileText, color: 'text-orange-600' },
  { value: 'practice', label: 'Practice', icon: Target, color: 'text-purple-600' },
  { value: 'certification', label: 'Certification', icon: Award, color: 'text-yellow-600' },
  { value: 'other', label: 'Other', icon: Zap, color: 'text-indigo-600' },
];

const statusOptions = [
  { value: 'not_started', label: 'Not Started', color: 'bg-gray-100 text-gray-800' },
  { value: 'in_progress', label: 'In Progress', color: 'bg-blue-100 text-blue-800' },
  { value: 'completed', label: 'Completed', color: 'bg-green-100 text-green-800' },
  { value: 'paused', label: 'Paused', color: 'bg-yellow-100 text-yellow-800' },
];

export default function ActivitiesIndex() {
  const [activities, setActivities] = useState<Activity[]>([]);
  const [skills, setSkills] = useState<Skill[]>([]);
  const [loading, setLoading] = useState(true);
  const [isCreateModalOpen, setIsCreateModalOpen] = useState<boolean>(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState<boolean>(false);
  const [editingActivity, setEditingActivity] = useState<Activity | null>(null);
  const [formData, setFormData] = useState<ActivityFormData>({
    name: '',
    description: '',
    type: 'course',
    status: 'not_started',
    url: '',
    estimated_hours: '',
    actual_hours: '',
    skill_id: '',
    dependencies: []
  });
  const [formErrors, setFormErrors] = useState<{[key: string]: string}>({});

  // Fetch data
  const fetchData = async () => {
    try {
      const [activitiesRes, skillsRes] = await Promise.all([
        fetch('/api/activities'),
        fetch('/api/skills')
      ]);
      
      const [activitiesData, skillsData] = await Promise.all([
        activitiesRes.json(),
        skillsRes.json()
      ]);
      
      setActivities(activitiesData);
      setSkills(skillsData);
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
    }
  };

  // Load data on mount
  useEffect(() => {
    fetchData();
  }, []);

  // Handle form changes
  const handleFormChange = (field: keyof ActivityFormData, value: string | string[]) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
    // Clear error when user starts typing
    if (formErrors[field]) {
      setFormErrors(prev => ({
        ...prev,
        [field]: ''
      }));
    }
  };

  // Handle create activity
  const handleCreateActivity = async (e: React.FormEvent) => {
    e.preventDefault();
    setFormErrors({});

    const payload = {
      name: formData.name,
      description: formData.description || null,
      type: formData.type,
      status: formData.status,
      url: formData.url || null,
      estimated_hours: formData.estimated_hours ? parseInt(formData.estimated_hours) : null,
      actual_hours: formData.actual_hours ? parseInt(formData.actual_hours) : null,
      skill_id: parseInt(formData.skill_id),
      dependencies: formData.dependencies.length > 0 ? formData.dependencies.map(id => parseInt(id)) : []
    };

    try {
      const response = await fetch('/api/activities', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify(payload),
      });

      if (response.ok) {
        const newActivity = await response.json();
        setActivities(prev => [...prev, newActivity]);
        setIsCreateModalOpen(false);
        resetForm();
      } else {
        const errorData = await response.json();
        if (errorData.errors) {
          setFormErrors(errorData.errors);
        }
      }
    } catch (error) {
      console.error('Error creating activity:', error);
    }
  };

  // Handle edit activity
  const handleEditActivity = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!editingActivity) return;

    setFormErrors({});

    const payload = {
      name: formData.name,
      description: formData.description || null,
      type: formData.type,
      status: formData.status,
      url: formData.url || null,
      estimated_hours: formData.estimated_hours ? parseInt(formData.estimated_hours) : null,
      actual_hours: formData.actual_hours ? parseInt(formData.actual_hours) : null,
      skill_id: parseInt(formData.skill_id),
      dependencies: formData.dependencies.length > 0 ? formData.dependencies.map(id => parseInt(id)) : []
    };

    try {
      const response = await fetch(`/api/activities/${editingActivity.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify(payload),
      });

      if (response.ok) {
        const updatedActivity = await response.json();
        setActivities(prev => prev.map(activity => 
          activity.id === editingActivity.id ? updatedActivity : activity
        ));
        setIsEditModalOpen(false);
        setEditingActivity(null);
        resetForm();
      } else {
        const errorData = await response.json();
        if (errorData.errors) {
          setFormErrors(errorData.errors);
        }
      }
    } catch (error) {
      console.error('Error updating activity:', error);
    }
  };

  // Handle delete activity
  const handleDeleteActivity = async (activity: Activity) => {
    if (!confirm(`Are you sure you want to delete "${activity.name}"?`)) {
      return;
    }

    try {
      const response = await fetch(`/api/activities/${activity.id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
      });

      if (response.ok) {
        setActivities(prev => prev.filter(a => a.id !== activity.id));
      }
    } catch (error) {
      console.error('Error deleting activity:', error);
    }
  };

  // Reset form
  const resetForm = () => {
    setFormData({
      name: '',
      description: '',
      type: 'course',
      status: 'not_started',
      url: '',
      estimated_hours: '',
      actual_hours: '',
      skill_id: '',
      dependencies: []
    });
  };

  // Open edit modal
  const openEditModal = (activity: Activity) => {
    setEditingActivity(activity);
    setFormData({
      name: activity.name,
      description: activity.description || '',
      type: activity.type,
      status: activity.status,
      url: activity.url || '',
      estimated_hours: activity.estimated_hours?.toString() || '',
      actual_hours: activity.actual_hours?.toString() || '',
      skill_id: activity.skill_id.toString(),
      dependencies: activity.depends_on.map(dep => dep.id.toString())
    });
    setIsEditModalOpen(true);
  };

  // Get type icon and color
  const getTypeInfo = (type: Activity['type']) => {
    const typeInfo = activityTypes.find(t => t.value === type);
    return typeInfo || activityTypes[0];
  };

  // Get status badge color
  const getStatusBadgeColor = (status: Activity['status']) => {
    const statusInfo = statusOptions.find(s => s.value === status);
    return statusInfo?.color || 'bg-gray-100 text-gray-800';
  };

  // Get available dependencies (exclude current activity and its dependents)
  const getAvailableDependencies = () => {
    if (!editingActivity) return activities;
    
    const dependentIds = new Set([editingActivity.id]);
    
    const addDependents = (activityId: number) => {
      const dependents = activities.filter(a => 
        a.depends_on.some(dep => dep.id === activityId)
      );
      dependents.forEach(dep => {
        if (!dependentIds.has(dep.id)) {
          dependentIds.add(dep.id);
          addDependents(dep.id);
        }
      });
    };
    
    addDependents(editingActivity.id);
    
    return activities.filter(a => !dependentIds.has(a.id));
  };

  if (loading) {
    return (
      <AppLayout>
        <Head title="Activities Management" />
        <div className="flex items-center justify-center h-64">
          <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
        </div>
      </AppLayout>
    );
  }

  return (
    <AppLayout>
      <Head title="Activities Management" />
      
      <div className="space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Activities Management</h1>
            <p className="text-gray-600">Manage your learning activities and track progress</p>
          </div>
          <div className="flex items-center space-x-4">
            <Button onClick={() => router.visit('/skill-graph')} variant="outline">
              View Skill Graph
            </Button>
            <Dialog open={isCreateModalOpen} onOpenChange={setIsCreateModalOpen}>
              <DialogTrigger asChild>
                <Button disabled={skills.length === 0}>
                  <Plus className="w-4 h-4 mr-2" />
                  Add Activity
                </Button>
              </DialogTrigger>
              <DialogContent className="max-w-2xl">
                <DialogHeader>
                  <DialogTitle>Create New Activity</DialogTitle>
                </DialogHeader>
                <form onSubmit={handleCreateActivity} className="space-y-4">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <Label htmlFor="name">Name</Label>
                      <Input
                        id="name"
                        value={formData.name}
                        onChange={(e) => handleFormChange('name', e.target.value)}
                        placeholder="Activity name"
                        required
                      />
                      {formErrors.name && <p className="text-sm text-red-600">{formErrors.name}</p>}
                    </div>
                    <div>
                      <Label htmlFor="type">Type</Label>
                      <Select value={formData.type} onValueChange={(value) => handleFormChange('type', value)}>
                        <SelectTrigger>
                          <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                          {activityTypes.map(type => (
                            <SelectItem key={type.value} value={type.value}>
                              <div className="flex items-center space-x-2">
                                <type.icon className={`w-4 h-4 ${type.color}`} />
                                <span>{type.label}</span>
                              </div>
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </div>
                  </div>
                  
                  <div>
                    <Label htmlFor="description">Description</Label>
                    <Textarea
                      id="description"
                      value={formData.description}
                      onChange={(e) => handleFormChange('description', e.target.value)}
                      placeholder="Brief description..."
                      rows={3}
                    />
                  </div>
                  
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <Label htmlFor="skill">Skill</Label>
                      <Select value={formData.skill_id} onValueChange={(value) => handleFormChange('skill_id', value)}>
                        <SelectTrigger>
                          <SelectValue placeholder="Select skill" />
                        </SelectTrigger>
                        <SelectContent>
                          {skills.map(skill => (
                            <SelectItem key={skill.id} value={skill.id.toString()}>
                              <div className="flex items-center space-x-2">
                                <div className="w-3 h-3 rounded-full" style={{ backgroundColor: skill.color }} />
                                <span>{skill.name}</span>
                              </div>
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                      {formErrors.skill_id && <p className="text-sm text-red-600">{formErrors.skill_id}</p>}
                    </div>
                    <div>
                      <Label htmlFor="status">Status</Label>
                      <Select value={formData.status} onValueChange={(value) => handleFormChange('status', value)}>
                        <SelectTrigger>
                          <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                          {statusOptions.map(status => (
                            <SelectItem key={status.value} value={status.value}>
                              {status.label}
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </div>
                  </div>
                  
                  <div>
                    <Label htmlFor="url">URL (Optional)</Label>
                    <Input
                      id="url"
                      type="url"
                      value={formData.url}
                      onChange={(e) => handleFormChange('url', e.target.value)}
                      placeholder="https://..."
                    />
                  </div>
                  
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <Label htmlFor="estimated_hours">Estimated Hours</Label>
                      <Input
                        id="estimated_hours"
                        type="number"
                        value={formData.estimated_hours}
                        onChange={(e) => handleFormChange('estimated_hours', e.target.value)}
                        placeholder="0"
                        min="0"
                      />
                    </div>
                    <div>
                      <Label htmlFor="actual_hours">Actual Hours</Label>
                      <Input
                        id="actual_hours"
                        type="number"
                        value={formData.actual_hours}
                        onChange={(e) => handleFormChange('actual_hours', e.target.value)}
                        placeholder="0"
                        min="0"
                      />
                    </div>
                  </div>
                  
                  <div>
                    <Label>Dependencies</Label>
                    <div className="space-y-2 max-h-32 overflow-y-auto">
                      {activities.map(activity => (
                        <div key={activity.id} className="flex items-center space-x-2">
                          <input
                            type="checkbox"
                            id={`dep-${activity.id}`}
                            checked={formData.dependencies.includes(activity.id.toString())}
                            onChange={(e) => {
                              if (e.target.checked) {
                                handleFormChange('dependencies', [...formData.dependencies, activity.id.toString()]);
                              } else {
                                handleFormChange('dependencies', formData.dependencies.filter(id => id !== activity.id.toString()));
                              }
                            }}
                          />
                          <label htmlFor={`dep-${activity.id}`} className="text-sm">
                            {activity.name} ({activity.skill.name})
                          </label>
                        </div>
                      ))}
                    </div>
                  </div>
                  
                  <div className="flex justify-end space-x-2">
                    <Button type="button" variant="outline" onClick={() => setIsCreateModalOpen(false)}>
                      Cancel
                    </Button>
                    <Button type="submit">Create Activity</Button>
                  </div>
                </form>
              </DialogContent>
            </Dialog>
          </div>
        </div>

        {/* Activities Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {activities.map((activity) => {
            const typeInfo = getTypeInfo(activity.type);
            const TypeIcon = typeInfo.icon;
            
            return (
              <Card key={activity.id} className="hover:shadow-lg transition-shadow">
                <CardHeader className="pb-3">
                  <div className="flex items-center justify-between">
                    <div className="flex items-center space-x-3">
                      <TypeIcon className={`w-5 h-5 ${typeInfo.color}`} />
                      <div className="w-3 h-3 rounded-full" style={{ backgroundColor: activity.skill.color }} />
                    </div>
                    <div className="flex items-center space-x-2">
                      {activity.url && (
                        <Button
                          size="sm"
                          variant="outline"
                          onClick={() => window.open(activity.url, '_blank')}
                        >
                          <ExternalLink className="w-4 h-4" />
                        </Button>
                      )}
                      <Button
                        size="sm"
                        variant="outline"
                        onClick={() => openEditModal(activity)}
                      >
                        <Edit className="w-4 h-4" />
                      </Button>
                      <Button
                        size="sm"
                        variant="outline"
                        onClick={() => handleDeleteActivity(activity)}
                      >
                        <Trash2 className="w-4 h-4" />
                      </Button>
                    </div>
                  </div>
                  <CardTitle className="text-lg">{activity.name}</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-3">
                    <div className="flex items-center justify-between">
                      <span className="text-sm text-gray-600">Status</span>
                      <Badge className={`text-xs ${getStatusBadgeColor(activity.status)}`}>
                        {activity.status.replace('_', ' ')}
                      </Badge>
                    </div>
                    <div className="flex items-center justify-between">
                      <span className="text-sm text-gray-600">Skill</span>
                      <span className="text-sm font-medium">{activity.skill.name}</span>
                    </div>
                    <div className="flex items-center justify-between">
                      <span className="text-sm text-gray-600">Type</span>
                      <span className="text-sm">{typeInfo.label}</span>
                    </div>
                    {activity.depends_on.length > 0 && (
                      <div>
                        <span className="text-sm text-gray-600">Dependencies</span>
                        <div className="flex flex-wrap gap-1 mt-1">
                          {activity.depends_on.map(dep => (
                            <Badge key={dep.id} variant="outline" className="text-xs">
                              {dep.name}
                            </Badge>
                          ))}
                        </div>
                      </div>
                    )}
                    {activity.description && (
                      <p className="text-sm text-gray-600 line-clamp-2">
                        {activity.description}
                      </p>
                    )}
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* Edit Modal */}
        <Dialog open={isEditModalOpen} onOpenChange={setIsEditModalOpen}>
          <DialogContent className="max-w-2xl">
            <DialogHeader>
              <DialogTitle>Edit Activity</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleEditActivity} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="edit-name">Name</Label>
                  <Input
                    id="edit-name"
                    value={formData.name}
                    onChange={(e) => handleFormChange('name', e.target.value)}
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="edit-type">Type</Label>
                  <Select value={formData.type} onValueChange={(value) => handleFormChange('type', value)}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {activityTypes.map(type => (
                        <SelectItem key={type.value} value={type.value}>
                          <div className="flex items-center space-x-2">
                            <type.icon className={`w-4 h-4 ${type.color}`} />
                            <span>{type.label}</span>
                          </div>
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>
              
              <div>
                <Label htmlFor="edit-description">Description</Label>
                <Textarea
                  id="edit-description"
                  value={formData.description}
                  onChange={(e) => handleFormChange('description', e.target.value)}
                  rows={3}
                />
              </div>
              
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="edit-skill">Skill</Label>
                  <Select value={formData.skill_id} onValueChange={(value) => handleFormChange('skill_id', value)}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {skills.map(skill => (
                        <SelectItem key={skill.id} value={skill.id.toString()}>
                          <div className="flex items-center space-x-2">
                            <div className="w-3 h-3 rounded-full" style={{ backgroundColor: skill.color }} />
                            <span>{skill.name}</span>
                          </div>
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label htmlFor="edit-status">Status</Label>
                  <Select value={formData.status} onValueChange={(value) => handleFormChange('status', value)}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {statusOptions.map(status => (
                        <SelectItem key={status.value} value={status.value}>
                          {status.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>
              
              <div>
                <Label htmlFor="edit-url">URL (Optional)</Label>
                <Input
                  id="edit-url"
                  type="url"
                  value={formData.url}
                  onChange={(e) => handleFormChange('url', e.target.value)}
                />
              </div>
              
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="edit-estimated_hours">Estimated Hours</Label>
                  <Input
                    id="edit-estimated_hours"
                    type="number"
                    value={formData.estimated_hours}
                    onChange={(e) => handleFormChange('estimated_hours', e.target.value)}
                    min="0"
                  />
                </div>
                <div>
                  <Label htmlFor="edit-actual_hours">Actual Hours</Label>
                  <Input
                    id="edit-actual_hours"
                    type="number"
                    value={formData.actual_hours}
                    onChange={(e) => handleFormChange('actual_hours', e.target.value)}
                    min="0"
                  />
                </div>
              </div>
              
              <div>
                <Label>Dependencies</Label>
                <div className="space-y-2 max-h-32 overflow-y-auto">
                  {getAvailableDependencies().map(activity => (
                    <div key={activity.id} className="flex items-center space-x-2">
                      <input
                        type="checkbox"
                        id={`edit-dep-${activity.id}`}
                        checked={formData.dependencies.includes(activity.id.toString())}
                        onChange={(e) => {
                          if (e.target.checked) {
                            handleFormChange('dependencies', [...formData.dependencies, activity.id.toString()]);
                          } else {
                            handleFormChange('dependencies', formData.dependencies.filter(id => id !== activity.id.toString()));
                          }
                        }}
                      />
                      <label htmlFor={`edit-dep-${activity.id}`} className="text-sm">
                        {activity.name} ({activity.skill.name})
                      </label>
                    </div>
                  ))}
                </div>
              </div>
              
              <div className="flex justify-end space-x-2">
                <Button type="button" variant="outline" onClick={() => setIsEditModalOpen(false)}>
                  Cancel
                </Button>
                <Button type="submit">Update Activity</Button>
              </div>
            </form>
          </DialogContent>
        </Dialog>

        {/* Empty State */}
        {activities.length === 0 && (
          <div className="text-center py-12">
            <Target className="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">No activities yet</h3>
            <p className="text-gray-600 mb-4">
              {skills.length === 0 ? 'Create a skill first, then add activities' : 'Get started by creating your first activity'}
            </p>
            {skills.length === 0 ? (
              <Button onClick={() => router.visit('/skills')}>
                Create Your First Skill
              </Button>
            ) : (
              <Button onClick={() => setIsCreateModalOpen(true)}>
                <Plus className="w-4 h-4 mr-2" />
                Create Your First Activity
              </Button>
            )}
          </div>
        )}
      </div>
    </AppLayout>
  );
} 